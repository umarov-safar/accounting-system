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
    "DOCKER_IMAGE_ADDRESS",  // название образа с доменом (harbor.gs.ru/project/service)
    "DOCKER_IMAGE_NAME",     // название образа без домена (project/service)
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
                doDeploy = params.DEPLOY_K8S || options.getAsList("AUTODEPLOY_BRANCHES").contains(BRANCH_NAME)

                if (doDeploy) {
                    cloneToFolder('ms-helm-values', options.get("VALUES_REPO"), options.get("VALUES_BRANCH"), options.get("GIT_CREDENTIALS_ID"))

                    def branchFolder = "ms-helm-values/${options.get("VALUES_PATH")}/${env.BRANCH_NAME}/${options.get("HELM_RELEASE")}"
                    def masterFolder = "ms-helm-values/${options.get("VALUES_PATH")}/master/${options.get("HELM_RELEASE")}"
                    helm.addFirstExisting([
                        "${branchFolder}/${options.get("HELM_RELEASE")}.yaml",
                        "${masterFolder}/${options.get("HELM_RELEASE")}.yaml"
                    ])
                    helm.addFirstExistingOptional([
                        "${branchFolder}/${options.get("HELM_RELEASE")}.sops.yaml",
                        "${masterFolder}/${options.get("HELM_RELEASE")}.sops.yaml"
                    ])

                    helm.addFirstExistingOptional([
                        "ms-helm-values/${options.get("COMMON_VALUES_PATH")}/common-env.yaml",
                    ])

                    cloneToFolder('ms-helm-chart', options.get("CHART_REPO"), options.get("CHART_BRANCH"), options.get("GIT_CREDENTIALS_ID"))
                }
                dir('src') {
                    checkout scm
                    gitCommit = sh(returnStdout: true, script: 'git log -1 --format=%h').trim();
                    dockerTag = "${env.BRANCH_NAME}-${gitCommit}"
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

        if (doDeploy) {
            gitlabCommitStatus("build") {
                stage('Build') {
                    dir('src') {
                        def imageExists = imageExistsInRegistry(
                            options.get("REGISTRY_CREDS"),
                            options.get("HARBOR_ADDRESS"),
                            options.get('DOCKER_IMAGE_NAME'),
                            dockerTag
                        )

                        if (!imageExists) {
                            withCredentials([string(credentialsId: options.get("GITLAB_TOKEN_CREDS"), variable: 'gitlabToken')]) {
                                docker.image(options.get("BASE_CI_IMAGE")).inside('--entrypoint=""') {
                                    sh(script: """
                                    composer install --no-ansi --no-interaction --no-suggest --no-dev --ignore-platform-reqs
                                    composer dump -o
                                """)
                                }
                            }
                            def fullImageNameWithTag = "${options.get('DOCKER_IMAGE_ADDRESS')}:${dockerTag}"
                            def image = docker.build(fullImageNameWithTag, "--build-arg BASE_IMAGE=${options.get("BASE_IMAGE")} .")

                            docker.withRegistry(options.get("HARBOR_ADDRESS"), options.get("REGISTRY_CREDS")) {
                                image.push(dockerTag)
                            }
                            sh """docker images |\
                                grep ${options.get('DOCKER_IMAGE_ADDRESS')} |\
                                grep ${env.BRANCH_NAME}- |\
                                grep -v ${gitCommit} |\
                                awk '{print \$1 ":" \$2 }' |\
                                xargs -r docker rmi"""

                        }
                    }
                }
            }

            stage('Deploy') {
                gitlabCommitStatus("deploy") {
                    def continueDeploy = false
                    if (params.PAUSE_BEFORE_DEPLOY) {
                        continueDeploy = input(
                            id: 'userInput',
                            message: 'Продолжить отгрузку?',
                            parameters: [
                                [$class: 'BooleanParameterDefinition', defaultValue: true, name: 'Deploy in k8s']
                            ]
                        )
                    } else {
                        continueDeploy = true
                    }

                    if (continueDeploy) {
                        def releaseName = "${options.get('HELM_RELEASE')}-${options.get("VALUES_BRANCH")}".replace("_", "-")
                        def svcName = ""
                        def ingressHost = ""

                        helm
                            .setValue("app.image.repository", options.get('DOCKER_IMAGE_ADDRESS'))
                            .setValue("app.image.tag", dockerTag)
                            .setValue("web.service.name", releaseName)
                            .setValue("hook.enabled", params.RUN_PRE_INSTALL_HOOK)

                        if (params.DELETE_AFTER && !options.getAsList('NOT_AUTODELETE').contains(options.get("VALUES_BRANCH"))) {
                            helm.setValue("app.deleteAfter", "+${params.DELETE_AFTER}h")
                        }

                        def helmParamsStr = helm.buildParams(options.get('SOPS_IMAGE'), options.get('SOPS_URL'))

                        docker.image(options.get("HELM_IMAGE")).inside('--entrypoint=""') {
                            withCredentials([file(credentialsId: options.get("K8S_CREDS"), variable: 'kubecfg')]) {
                                sh """KUBECONFIG=${kubecfg} \
                                    helm upgrade --install --timeout=30m \
                                    ${helmParamsStr} \
                                    --namespace ${options.get('K8S_NAMESPACE')} \
                                    ${releaseName} ms-helm-chart"""

                                svcName = sh(returnStdout: true, script: """helm template -s templates/web-svc.yaml \
                                    ${helmParamsStr} \
                                    ${releaseName} ms-helm-chart \
                                    |  awk '/^\\s+name:/ {print \$2}' | head -1
                                    """).trim()

                                ingressHost = sh(returnStdout: true, script: """helm template -s templates/web-ing.yaml \
                                    ${helmParamsStr} \
                                    ${releaseName} ms-helm-chart \
                                    | awk '/host:/ {print \$3}' | sed 's/"//g'
                                    """).trim()
                            }
                        }

                        currentBuild.description = [
                            "Docker image: ${options.get('DOCKER_IMAGE_ADDRESS')}:${dockerTag}",
                            "Internal host: http://${svcName}.${options.get('K8S_NAMESPACE')}.svc.cluster.local",
                            "Public host: https://${ingressHost}"
                        ].join("\n")

                    }
                }
            }
        }
    }
}
