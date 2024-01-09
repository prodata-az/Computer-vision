FROM node:18
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY ./ .
#RUN npm run build
ENV HOST 0.0.0.0
