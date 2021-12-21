FROM node:16

# Configure permissions
ARG UID=1000
RUN userdel node && useradd --uid $UID --create-home user
USER user

WORKDIR /app
