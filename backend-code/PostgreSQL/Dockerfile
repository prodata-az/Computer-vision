# Use the official PostgreSQL image as the base image
FROM postgres:16

# Install the pgvector extension
RUN apt-get update \
    && apt-get install -y postgresql-16-pgvector \
    && rm -rf /var/lib/apt/lists/*