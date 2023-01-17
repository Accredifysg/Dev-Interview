# Create ECS Task Definition

## Requirements

* AWS account

## Objectives

1. Write Terraform configuration for creating task definition
2. It should listens on Port 80
3. Runs on Linux Platform
4. Using the latest image that stored in ECR
5. Using the given parameters/variables below
7. Run the commands to apply the configuration and creating task definition
8. What happens if you run again `terraform apply`?
9. Destroy the resource you've created with Terraform

## Variables

Use the following variables for definition

### For CPU and Memory
```
cpu                      = var.ecs_task_cpu
memory                   = var.ecs_task_memory
```

### For execution_role_arn & task_role_arn
```
execution_role_arn       = aws_iam_role.ecs-task-execution-role.arn
task_role_arn            = aws_iam_role.ecs-task-execution-role.arn
```

### For Health-check
```
"curl -f http://localhost/health-check || exit 1"
```

### For Secrets
```
      secrets = local.secrets
```
