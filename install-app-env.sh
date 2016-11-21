#!/bin/bash

#Database creation
aws rds create-db-instance --db-name nighters --db-instance-identifier db-nighters --engine mariadb --db-instance-class db.t2.micro --allocated-storage 5 --master-username nighter --master-user-password nighter-password

aws rds wait db-instance-available --db-instance-identifier db-nighters

echo "Mariadb database created"

#S3 Bucket
aws s3 mb s3://nighters

echo "Bucket created: nighters"
