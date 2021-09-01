#!groovy
// -*- coding: utf-8; mode: Groovy; -*-


def vars = [:]

/**
 * Список переменных, котоыре необходимы для работы пайплайна.
 * Их можно определить в ConfigFiles с именами env-folder и env-service
 */
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
    "K8S_CREDS",             // credentials id от kubeconfig
    "TESTING_DB_HOST",
    "POSTGRES_TEST_CREDS",
    "PSQL_IMAGE"
]

/**
 * Список переменных, которые можно переопределить при запуске пайплайна.
 */
def inputVarsList = [
    "K8S_NAMESPACE",
    "HELM_RELEASE",
    "CHART_BRANCH",
    "VALUES_BRANCH",
    "VALUES_PATH",
    "BASE_IMAGE",
    "BASE_CI_IMAGE"
]

/**
 * Получить параметр для текущего job или folder из глобальных env переменных.
 * @return String|null
 */
def getFolderParam(env, name) {
    def pathParts = env["JOB_NAME"].split("/") as List
    while (pathParts.size() > 0) {
        def path = pathParts.join("_")
        def key = "PROP_${path}_${name}"
        def value = env[key]
        if (value) {
            return value
        } else {
            pathParts.remove(pathParts.size() - 1)
        }
    }

    return null
}

/**
 * Загрузить параметры в Map из env файла с указанным кодом
 * @return Map
 */
def loadVarsFromConfigFile(vars, configFileCode) {
    try {
        configFileProvider([configFile(fileId: configFileCode, targetLocation: "./${configFileCode}.txt")]) {
            def propsFromFile = readProperties(file: "./${configFileCode}.txt")
            for (prop in propsFromFile) {
                vars."${prop.key}" = "${prop.value}"
            }
        }
    } catch (Exception e) {}

    return vars
}

/**
 * Проверить наличие указанных ключей в Map с параметрами сборки.
 */
def checkRequiredVars(vars, requiredKeys) {
    def missingKeys = []
    for (key in requiredKeys) {
        if (!vars.containsKey(key)) {
            missingKeys.add(key)
        }
    }
    if (missingKeys.size() > 0) {
        missingKeysStr = missingKeys.join(", ")
        print(vars)
        error("В ConfigFiles отсутсвуют параметры: ${missingKeysStr}")
    }
}

/**
 * Клонировать репозиторий в указанную папку.
 */
def cloneToFolder(folderName, repoUrl, branch, credentialsId) {
    if (!fileExists(folderName)){
        new File(folderName).mkdir()
    }
    dir (folderName) {
        git([url: repoUrl, branch: branch, credentialsId: credentialsId])
    }
}

/**
 * Загрузить параметры сборки из ConfigFiles и из параметров запуска.
 */
def loadVariables(vars, inputVars, requiredVarsList, inputVarsList) {
    loadVarsFromConfigFile(vars, "env-folder")
    loadVarsFromConfigFile(vars, "env-service")

    for (key in inputVarsList) {
        if (inputVars[key]) {
            vars[key] = inputVars[key]
        }
    }

    checkRequiredVars(vars, requiredVarsList)
}

/**
 * Сгенерировать список опций для запуска на основании списка необходимых переменных.
 */
def generateParametersList(inputVarsList, parameters) {
    for (key in inputVarsList) {
        parameters.add(string(name: key, defaultValue: '', description: "Переопределить ${key}"))
    }
    return parameters
}

/* ================================= */

// def stagesStr = getFolderParam(env, "STAGES")
// def choices = stagesStr.split(',') as List

properties([
    gitLabConnection('public-gitlab'),
    parameters(
        generateParametersList(inputVarsList, [
            booleanParam(name: 'PAUSE_BEFORE_DEPLOY', defaultValue: false, description: 'Запросить подтверждение перед отгрузкой'),
            booleanParam(name: 'RUN_PRE_INSTALL_HOOK', defaultValue: true, description: 'Выполнить миграции перед отгрузкой')
        ])
    ),
    buildDiscarder(logRotator (artifactDaysToKeepStr: '', artifactNumToKeepStr: '10', daysToKeepStr: '', numToKeepStr: '10')),
    disableConcurrentBuilds(),
])

def doDeploy = getFolderParam(env, "DEPLOY") == "true"
def gitCommit = ''
def dockerTag = ''
def fullImageNameWithTag = ''
def configPath = ''
def imageExists = false
def image = ''
def testOk = true

node('docker-agent'){
    stage('Checkout') {
        gitlabCommitStatus("checkout") {
            cleanWs()
            loadVariables(vars, params, configVarsList, inputVarsList)
            if (doDeploy) {
                cloneToFolder('ms-helm-values', vars["VALUES_REPO"], vars["VALUES_BRANCH"], vars["GIT_CREDENTIALS_ID"])

                configPath = "ms-helm-values/${vars["VALUES_PATH"]}/${env.BRANCH_NAME}/${vars["HELM_RELEASE"]}.yaml"
                if (!fileExists(configPath)) {
                    configPath = "ms-helm-values/${vars["VALUES_PATH"]}/master/${vars["HELM_RELEASE"]}.yaml"
                }

                if (!fileExists(configPath)) {
                    error("Файл ${configPath} не найден")
                }

                cloneToFolder('ms-helm-chart', vars["CHART_REPO"], vars["CHART_BRANCH"], vars["GIT_CREDENTIALS_ID"])
            }
            dir ('src') {
                checkout scm
                gitCommit = sh(returnStdout:true, script: 'git log -1 --format=%h').trim();
                dockerTag = "${env.BRANCH_NAME}-${gitCommit}"
                fullImageNameWithTag = "${vars['DOCKER_IMAGE_ADDRESS']}:${dockerTag}"
            }
        }
    }

    stage('Test') {
        gitlabCommitStatus("test") {
            dir ('src') {
                if (!vars["DISABLE_QA"]) {
                    def dbPrefix = doDeploy ? "deploy" : "test";
                    def dbName = "ci_auto_${dbPrefix}_${vars["HELM_RELEASE"]}_${env.BRANCH_NAME}".replace("-", "_").toLowerCase()

                    withCredentials([usernamePassword(credentialsId: vars["POSTGRES_TEST_CREDS"], usernameVariable: 'username', passwordVariable: 'password')]) {
                         docker.image(vars["PSQL_IMAGE"]).inside('--entrypoint=""') {
                            sh(script: """
                                export PGPASSWORD=${password}
                                databases=\$(psql --username=${username} --dbname=postgres --host=${vars["TESTING_DB_HOST"]} -t --csv --command '\\l' | grep ${dbName} | awk 'BEGIN {FS=","}; {print \$1}')
                                for i in \$databases; do
                                    psql --username=${username} --dbname=postgres --host=${vars["TESTING_DB_HOST"]} --command="DROP DATABASE IF EXISTS \$i;"
                                done
                                psql --username=${username} --dbname=postgres --host=${vars["TESTING_DB_HOST"]} --command='CREATE DATABASE ${dbName};'
                            """)
                        }

                        withCredentials([string(credentialsId: vars["GITLAB_TOKEN_CREDS"], variable: 'gitlabToken')]) {
                            docker.image(vars["BASE_CI_IMAGE"]).inside("--entrypoint=''") {
                                sh(script: """
                                    composer config gitlab-oauth.gitlab.com ${gitlabToken}

                                    composer install --no-ansi --no-interaction --no-suggest --ignore-platform-reqs
                                    composer dump -o
                                """)

                                testOk = 0 == sh(script: """
                                    export DB_CONNECTION=pgsql
                                    export DB_HOST=${vars["TESTING_DB_HOST"]}
                                    export DB_PORT=5432
                                    export DB_DATABASE=${dbName}
                                    export DB_USERNAME=${username}
                                    export DB_PASSWORD=${password}

                                    composer test-ci
                                """, returnStatus:true)
                            }
                        }

                        step([
                            $class: 'CloverPublisher',
                            cloverReportDir: 'build',
                            cloverReportFileName: 'clover.xml',
                            healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80], // optional, default is: method=70, conditional=80, statement=80
                            unhealthyTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50], // optional, default is none
                            failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]     // optional, default is none
                        ])

                        docker.image(vars["PSQL_IMAGE"]).inside('--entrypoint=""') {
                            sh(script: """
                                export PGPASSWORD=${password}
                                databases=\$(psql --username=${username} --dbname=postgres --host=${vars["TESTING_DB_HOST"]} -t --csv --command '\\l' | grep ${dbName} | awk 'BEGIN {FS=","}; {print \$1}')
                                for i in \$databases; do
                                    psql --username=${username} --dbname=postgres --host=${vars["TESTING_DB_HOST"]} --command="DROP DATABASE IF EXISTS \$i;"
                                done
                                psql --username=${username} --dbname=postgres --host=${vars["TESTING_DB_HOST"]} --command='DROP DATABASE IF EXISTS ${dbName};'
                            """)
                        }
                    }
                }
            }
        }
    }

    if (doDeploy) {
        gitlabCommitStatus("build") {
            stage('Check registry') {
                dir('src') {
                    try {
                        withCredentials([usernamePassword(credentialsId: vars["REGISTRY_CREDS"], usernameVariable: 'username', passwordVariable: 'password')]) {
                            def harborToken = sh(returnStdout:true, script: """
                                curl -s -k \
                                    -u '${username}:${password}' \
                                    '${vars["HARBOR_ADDRESS"]}/service/token?service=harbor-registry&scope=repository:${vars['DOCKER_IMAGE_NAME']}:pull' | \
                                    python -c 'import json,sys; obj = json.load(sys.stdin); print(obj["token"]);'
                            """)
                            def statusCode = sh(returnStdout:true, script: """
                                curl -s -k \
                                    -H 'Content-Type: application/json' \
                                    -H "Authorization:  Bearer ${harborToken}" \
                                    -X GET \
                                    -o /dev/null -w '%{http_code}' \
                                    '${vars["HARBOR_ADDRESS"]}/v2/${vars['DOCKER_IMAGE_NAME']}/manifests/${dockerTag}'
                            """).trim()

                            imageExists = "200" == statusCode
                        }
                    } catch (Exception e) {}
                }
            }
            stage('Build') {
                dir ('src') {
                    if (!imageExists) {
                        withCredentials([string(credentialsId: vars["GITLAB_TOKEN_CREDS"], variable: 'gitlabToken')]) {
                        docker.image(vars["BASE_CI_IMAGE"]).inside('--entrypoint=""') {
                            sh(script: """
                                composer install --no-ansi --no-interaction --no-suggest --no-dev --ignore-platform-reqs
                                composer dump -o
                            """)
                        }
                    }
                    image = docker.build(fullImageNameWithTag, "--build-arg BASE_IMAGE=${vars["BASE_IMAGE"]} .")

                    docker.withRegistry(vars["HARBOR_ADDRESS"], vars["REGISTRY_CREDS"]) {
                        image.push(dockerTag)
                    }
                    sh """docker images |\
                          grep ${vars['DOCKER_IMAGE_ADDRESS']} |\
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
                    def releaseName = "${vars['HELM_RELEASE']}-${env.BRANCH_NAME}".replace("_", "-")
                    docker.image(vars["HELM_IMAGE"]).inside('--entrypoint=""') {
                        withCredentials([file(credentialsId: vars["K8S_CREDS"], variable: 'kubecfg')]) {
                            sh """KUBECONFIG=${kubecfg} \
                                  helm upgrade --install --timeout=30m \
                                  --values ${configPath} \
                                  --set app.image.repository=${vars['DOCKER_IMAGE_ADDRESS']} \
                                  --set app.image.tag=${dockerTag} \
                                  --set web.service.name=${releaseName} \
                                  --set hook.enabled=${params.RUN_PRE_INSTALL_HOOK} \
                                  --namespace ${vars['K8S_NAMESPACE']} \
                                  ${releaseName} ms-helm-chart"""
                        }
                    }
                }
            }
        }
    }

    stage("Set status") {
        if (!testOk) {
            currentBuild.result = 'UNSTABLE'
        }
    }
}
