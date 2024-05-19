FROM node:lts-alpine as build

WORKDIR /app

COPY ./docker/frontend/package*.json ./

RUN npm ci

COPY ./docker/frontend/. .

RUN npm run build

FROM nginx:latest as prod

COPY --from=build /app/build /usr/share/nginx/html
COPY ./docker/frontend/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80/tcp

CMD ["/usr/sbin/nginx", "-g", "daemon off;"]

## Используем node образ для сборки
#FROM node:16 as build
#
## Рабочая директория
#WORKDIR /usr/src/app
#
## Копируем package.json и package-lock.json
#COPY ./docker/frontend/package.json /usr/src/app/package.json
#COPY ./docker/frontend/package-lock.json /usr/src/app/package-lock.json
#
## Устанавливаем зависимости
#RUN npm install
## Копируем остальные файлы
#COPY ./docker/frontend/. /usr/src/app
#
#RUN npm run build
#CMD npm run start
#
#FROM nginx:alpine
#
## Copy the build output to nginx's static files directory
#COPY --from=build /usr/src/app/build /usr/share/nginx/html
#
## Copy custom nginx configuration file
#COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
#
## Expose port
#EXPOSE 80
#
## Start nginx
#CMD ["nginx", "-g", "daemon off;"]