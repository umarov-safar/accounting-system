<?php

namespace App\Http\ApiV1\Support\Pagination\Tests;

use App\Http\ApiV1\Support\Pagination\OffsetPageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\UnitTestCase;

uses(UnitTestCase::class);
uses()->group('unit');

function ofb_make_request(?array $requestBody = null): Request
{
    $request = new Request();
    if ($requestBody) {
        $request->setMethod('POST');
        $request->request->add($requestBody);
    }

    return $request;
}

test('OffsetPageBuilder build with positive limit', function () {
    $limit = 10;
    $request = ofb_make_request([
        'pagination' => [
            'limit' => $limit,
        ],
    ]);
    $queryBuilderMock = $this->mockQueryBuilder();
    $expectedItems = [1, 2, 3, 4];
    $queryBuilderMock->shouldReceive('clone')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('skip')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('limit')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('get')->andReturn(new Collection($expectedItems));
    $builder = new OffsetPageBuilder($queryBuilderMock, $request);

    $page = $builder->build();

    expect($page->items)->toMatchArray($expectedItems);
    expect($page->pagination)->toMatchArray([
        "offset" => 0,
        "limit" => $limit,
        "total" => 4,
        "type" => "offset",
    ]);
});

test('OffsetPageBuilder build needs another count query if there is more items possibly', function () {
    $limit = 4;
    $total = 12;
    $request = ofb_make_request([
        'pagination' => [
            'limit' => $limit,
        ],
    ]);
    $queryBuilderMock = $this->mockQueryBuilder();
    $expectedItems = [1, 2, 3, 4];
    $queryBuilderMock->shouldReceive('clone')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('skip')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('limit')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('get')->andReturn(new Collection($expectedItems));
    $queryBuilderMock->shouldReceive('count')->andReturn($total);
    $builder = new OffsetPageBuilder($queryBuilderMock, $request);

    $page = $builder->build();

    expect($page->items)->toMatchArray($expectedItems);
    expect($page->pagination)->toMatchArray([
        "offset" => 0,
        "limit" => $limit,
        "total" => $total,
        "type" => "offset",
    ]);
});

test('OffsetPageBuilder build with 0 as limit returns empty array', function () {
    $limit = 0;
    $request = ofb_make_request([
        'pagination' => [
            'limit' => $limit,
        ],
    ]);
    $queryBuilderMock = $this->mockQueryBuilder();
    $builder = new OffsetPageBuilder($queryBuilderMock, $request);

    $page = $builder->build();

    expect($page->items)->toMatchArray([]);
    expect($page->pagination)->toMatchArray([
        "offset" => 0,
        "limit" => $limit,
        "total" => 0,
        "type" => "offset",
    ]);
});

test('OffsetPageBuilder build with negative limit', function () {
    $limit = -1;
    $request = ofb_make_request([
        'pagination' => [
            'limit' => $limit,
        ],
    ]);
    $queryBuilderMock = $this->mockQueryBuilder();
    $expectedItems = [1, 2, 3, 4];
    $queryBuilderMock->shouldReceive('get')->andReturn(new Collection($expectedItems));
    $builder = new OffsetPageBuilder($queryBuilderMock, $request);

    $page = $builder->build();

    expect($page->items)->toMatchArray($expectedItems);
    expect($page->pagination)->toMatchArray([
        "offset" => 0,
        "limit" => $limit,
        "total" => count($expectedItems),
        "type" => "offset",
    ]);
});

test('OffsetPageBuilder build with negative limit and forbidToBypassPagination=true', function () {
    $limit = -1;
    $request = ofb_make_request([
        'pagination' => [
            'limit' => $limit,
        ],
    ]);
    $queryBuilderMock = $this->mockQueryBuilder();
    $builder = new OffsetPageBuilder($queryBuilderMock, $request);

    $page = $builder->forbidToBypassPagination()->build();

    expect($page->items)->toMatchArray([]);
    expect($page->pagination)->toMatchArray([
        "offset" => 0,
        "limit" => $limit,
        "total" => 0,
        "type" => "offset",
    ]);
});

test('OffsetPageBuilder build with positive limit cannot exceed max limit', function () {
    $limit = 10;
    $maxLimit = 5;
    $request = ofb_make_request([
        'pagination' => [
            'limit' => $limit,
        ],
    ]);
    $queryBuilderMock = $this->mockQueryBuilder();
    $expectedItems = [1, 2, 3, 4];
    $queryBuilderMock->shouldReceive('clone')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('skip')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('limit')->andReturn($queryBuilderMock);
    $queryBuilderMock->shouldReceive('get')->andReturn(new Collection($expectedItems));

    $builder = new OffsetPageBuilder($queryBuilderMock, $request);

    $page = $builder->maxLimit($maxLimit)->build();

    expect($page->items)->toMatchArray($expectedItems);
    expect($page->pagination)->toMatchArray([
        "offset" => 0,
        "limit" => $maxLimit,
        "total" => count($expectedItems),
        "type" => "offset",
    ]);
});
