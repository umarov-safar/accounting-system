#!groovy
// -*- coding: utf-8; mode: Groovy; -*-

@Library('ru.greensight@v1.0.2')_

import ru.greensight.HelmParams
import ru.greensight.Options

def options = new Options(script:this)
def helm = new HelmParams(script:this)

def configVarsList = [
    "HELM_RELEASE",          // название helm релиза
    "GIT_CREDENTIALS_ID",    // credentials id от гитлаба
    "DOCKER_IMAGE_ADDRESS",  // название образа с доменом (harbor.gs.ru/project/service)
    "DOCKER_IMAGE_NAME",     // название образа без домена (project/service)
    "HARBOR_ADDRESS",        // адрес реджистри с протоколом (https://harbor.gs.ru)
    "REGISTRY_CREDS",        // credentials id от реджистри
    "BASE_CI_IMAGE",         // базовый образ для тестирования приложения
    "GITLAB_TOKEN_CREDS",    // credentials id с токеном гитлаба
    "K8S_CREDS",             // credentials id от kubeconfig
    "TESTING_DB_HOST",       // адрес СУБД для создания тестовых БД
    "POSTGRES_TEST_CREDS",   // credentials id от СУБД
    "PSQL_IMAGE",            // образ psql
    "AUTODEPLOY_BRANCHES"    // ветка для autodeploy
]

properties([
    gitLabConnection('public-gitlab'),
    parameters([
        string(name: 'VALUES_BRANCH', defaultValue: env.BRANCH_NAME, description: "config-store branch"),
        string(name: 'DELETE_AFTER', defaultValue: '336', description: "Delete application after N hours")
    ]),
    buildDiscarder(logRotator (artifactDaysToKeepStr: '', artifactNumToKeepStr: '10', daysToKeepStr: '', numToKeepStr: '10')),
    disableConcurrentBuilds(),
])

def doDeploy = ''
def gitCommit = ''
def dockerTag = ''

node('docker-agent'){
    lock(label: 'docker', quantity: 1) {
        stage('Test') {
            gitlabCommitStatus("test") {
                dir('src') {
                    if (!options.get("DISABLE_QA")) {
                        def dbPrefix = doDeploy ? "deploy" : "test";
                        def dbName = "ci_auto_${dbPrefix}_${options.get("HELM_RELEASE")}_${env.BRANCH_NAME}".replace("-", "_").toLowerCase()
                        def testStatus = 0

                        withCredentials([string(credentialsId: options.get("GITLAB_TOKEN_CREDS"), variable: 'gitlabToken')]) {
                            withCredentials([usernamePassword(credentialsId: options.get("POSTGRES_TEST_CREDS"), usernameVariable: 'username', passwordVariable: 'password')]) {
                                withPostgresDB(options.get("PSQL_IMAGE"), options.get("TESTING_DB_HOST"), username, password, dbName) {
                                    docker.image(options.get("BASE_CI_IMAGE")).inside("--entrypoint=''") {
                                        sh(script: """
                                            composer config gitlab-oauth.gitlab.com ${gitlabToken}

                                            composer install --no-ansi --no-interaction --no-suggest --ignore-platform-reqs
                                            composer dump -o
                                        """)

                                        testStatus = sh(script: """
                                            export DB_CONNECTION=pgsql
                                            export DB_HOST=${options.get("TESTING_DB_HOST")}
                                            export DB_PORT=5432
                                            export DB_DATABASE=${dbName}
                                            export DB_USERNAME=${username}
                                            export DB_PASSWORD=${password}

                                            composer test-ci
                                        """, returnStatus: true)
                                    }
                                }
                            }

                            step([
                                $class              : 'CloverPublisher',
                                cloverReportDir     : 'build',
                                cloverReportFileName: 'clover.xml',
                                healthyTarget       : [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80], // optional, default is: method=70, conditional=80, statement=80
                                unhealthyTarget     : [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50], // optional, default is none
                                failingTarget       : [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]     // optional, default is none
                            ])
                        }
                        if (testStatus != 0) {
                            error("Test failed")
                        }
                    }
                }
            }
        }
    }
}
