pipeline {
    agent any

    environment {
        GIT_COMMIT_SHORT = "${sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()}"
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/bawad/laravel-cicd-tutorial.git'
            }
        }

        stage('Create Dockerfile') {
            steps {
                writeFile file: 'Dockerfile', text: '''
                    FROM php:7.4-fpm

                    # Install required extensions and dependencies
                    RUN apt-get update && apt-get install -y \
                        libpng-dev \
                        libjpeg-dev \
                        libfreetype6-dev \
                        libzip-dev \
                        unzip \
                        git \
                        && docker-php-ext-configure gd --with-freetype --with-jpeg \
                        && docker-php-ext-install -j$(nproc) gd \
                        && docker-php-ext-install pdo_mysql zip

                    # Install Composer
                    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

                    # Set working directory
                    WORKDIR /var/www/html

                    # Copy application files
                    COPY . /var/www/html

                    # Install dependencies
                    RUN composer install --no-interaction --no-dev --optimize-autoloader

                    # Set permissions
                    RUN chown -R www-data:www-data /var/www/html/storage
                    RUN chown -R www-data:www-data /var/www/html/bootstrap/cache
                    '''
            }
        }

        stage('Build Docker Image') {
            steps {
                sh "docker build -t badranawad/laravel-docker-app:${env.GIT_COMMIT_SHORT} ."
            }
        }

        stage('Push Docker Image') {
            steps {
                withCredentials([string(credentialsId: 'badran.awd@gmail.com', variable: 'DOCKERHUB_TOKEN')]) {
                    sh "echo '${DOCKERHUB_TOKEN}' | docker login -u badranawad --password-stdin"
                    sh "docker push badranawad/laravel-docker-app:${env.GIT_COMMIT_SHORT}"
                }
            }
        }

        // stage('Deploy Laravel Application') {
        //     steps {
        //         // Replace 'ssh-credentials-id' with your SSH credentials ID in Jenkins
        //         sshagent(['ssh-credentials-id']) {
        //             // Replace 'your-deployment-server' with your server's IP or domain
        //             sh "ssh your-deployment-server 'docker pull your-dockerhub-username/your-image-name:${env.GIT_COMMIT_SHORT}'"
        //             sh "ssh your-deployment-server 'docker-compose down && docker-compose up -d --force-recreate'"
        //         }
        //     }
        // }
    }

    post {
        always {
            sh 'docker compose down --remove-orphans -v'
            sh 'docker compose ps'
        }

        failure {
            // Configure notifications if required, e.g., email, Slack, etc.
        }
    }
}
