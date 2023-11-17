# Stage 1: Build stage
FROM python:alpine3.18 as build
LABEL maintainer="David Castillo <vikruzdavid@gmail.com>"
WORKDIR /code
RUN apk add --no-cache gcc linux-headers alpine-sdk tesseract-ocr 
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt
# Descargar spa.traineddata y cambiar permisos
ADD https://github.com/tesseract-ocr/tessdata_best/raw/main/spa.traineddata /usr/share/tessdata/spa.traineddata

# Stage 2: Development stage
FROM build as dev
RUN pip install -r requirements.txt
ENV FLASK_ENV development
ENV FLASK_DEBUG 1
EXPOSE 5000
CMD flask run

# Stage 3: Production stage
FROM build as prod
RUN pip install --no-cache-dir -r requirements.txt
RUN apk del gcc musl-dev linux-headers alpine-sdk 
ENV FLASK_ENV production
EXPOSE 5000
CMD gunicorn -w 4 -b microservicio.compymedics.site:5000 app:app
