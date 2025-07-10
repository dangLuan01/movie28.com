# Stage 1: Build Astro
FROM node:20-alpine AS builder

WORKDIR /app

# Copy env file trước nếu dùng trong build
COPY .env .env

COPY package*.json ./
RUN npm install

COPY . .

RUN npm run build

# Stage 2: Serve static files using nginx
FROM nginx:alpine

COPY --from=builder /app/dist /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
