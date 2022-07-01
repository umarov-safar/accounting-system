#!groovy
// -*- coding: utf-8; mode: Groovy; -*-

@Library('ru.greensight@v1.0.2')_

import ru.greensight.HelmParams
import ru.greensight.Options

def options = new Options(script:this)
def helm = new HelmParams(script:this)

def configVarsList = [
    "K8S_NAMESPACE",         // неймспейс в который отгружать
    "HELM_RELEASE",          // название helm релиза
    "GIT_CREDENTIALS_ID",    // credentials id от гитлаба
    "VALUES_REPO",           // адрес репозитория values
    "VALUES_BRANCH",         // ветка в репозитории values
    "VALUES_PATH",           // путь до файла values в репозитории values
    "CHART_REPO",            // адрес репозитория чарта
    "CHART_BRANCH",          // ветка в репозитории чарта
    "HARBOR_ADDRESS",        // адрес реджистри с протоколом (https://harbor.gs.ru)
    "REGISTRY_CREDS",        // credentials id от реджистри
    "BASE_IMAGE",            // базовый образ для приложения (harbor.gs.ru/project/php:7.3)
    "BASE_CI_IMAGE",         // базовый образ для тестирования приложения
    "GITLAB_TOKEN_CREDS",    // credentials id с токеном гитлаба
    "HELM_IMAGE",            // образ helm
    "SOPS_IMAGE",            // образ sops
    "SOPS_URL",              // адрес sops keyservice
    "K8S_CREDS",             // credentials id от kubeconfig
    "TESTING_DB_HOST",       // адрес СУБД для создания тестовых БД
    "POSTGRES_TEST_CREDS",   // credentials id от СУБД
    "PSQL_IMAGE",            // образ psql
    "AUTODEPLOY_BRANCHES"    // ветка для autodeploy
]

properties([
    gitLabConnection('public-gitlab'),
    parameters([
        booleanParam(name: 'DEPLOY_K8S', defaultValue: false, description: 'Отгрузить в kubernetes'),
        booleanParam(name: 'PAUSE_BEFORE_DEPLOY', defaultValue: false, description: 'Ask user approvement before deploy'),
        booleanParam(name: 'RUN_PRE_INSTALL_HOOK', defaultValue: true, description: 'Execute migration before deploy'),
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
        stage('Checkout') {
            gitlabCommitStatus("checkout") {
                cleanWs()
                options.loadConfigFile("env-folder")
                options.loadConfigFile("env-service")
                options.checkDefined(configVarsList)
                dir('src') {
                    checkout scm
                    gitCommit = sh(returnStdout: true, script: 'git log -1 --format=%h').trim();
                }
            }
        }

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
