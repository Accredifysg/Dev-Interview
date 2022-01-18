FROM node:16.13.2-alpine as devBuild

RUN apk add --no-cache python3 make g++

WORKDIR /usr/src/app

COPY package*.json ./

RUN yarn

EXPOSE 8080

CMD ["npm", "run", "serve"]
