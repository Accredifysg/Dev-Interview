# Local Provider

## Objectives

Learn how to use and run Terraform basic commands

1. Create a directory called "my_first_run"
2. Inside the directory create a file called "main.tf" with the following content

```terraform
resource "local_file" "accredify_local_file" {
    content  = "It is located at Kallang Place!"
    filename = "/tmp/who_is_it.txt"
}
```
3. Run `terraform init`. What did it do?
4. Run `terraform plan`. What Terraform is going to perform?
5. Finally, run `terraform apply` and verify the file was created
