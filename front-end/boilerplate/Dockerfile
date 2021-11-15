FROM node:12.2.0-alpine as devBuild

WORKDIR /usr/src/app

COPY package*.json ./

RUN yarn

EXPOSE 8080

CMD ["npm", "run", "serve"]
