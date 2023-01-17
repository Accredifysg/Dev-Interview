# Create ECS Service with the given resource name, accredify-ecs-app

## Requirements

* AWS account

## Objectives

1. Write Terraform configuration for creating an ECS Service with the given resource name, accredify-ecs-app
2. Run the commands to apply the configuration and creating an ECS Service
3. What happens if you run again `terraform apply`?
4. Destroy the resource you've created with Terraform

## variables

Use the following for Security Groups & Subnets for network_configuration block:
```
security_groups  = [aws_security_group.accredify-ecs-service.id]
subnets          = var.private_app_subnets[*].id
```
