#!/bin/bash
docker run --rm -v $(pwd):/app composer install
sudo chown -R $USER:$USER .