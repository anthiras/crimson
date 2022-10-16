#!/bin/bash
docker run --rm -v $(pwd):/app composer:1.10.10 install
sudo chown -R $USER:$USER .