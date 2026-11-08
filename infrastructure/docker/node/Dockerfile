FROM node:20-alpine

WORKDIR /app

# Install dependencies first so the layer is cached between code changes.
# The lockfile is used for reproducible installs.
COPY frontend/package.json frontend/package-lock.json /app/
RUN npm ci --no-audit --no-fund

COPY frontend/ /app/

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
