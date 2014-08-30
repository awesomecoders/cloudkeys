# cloudkeys

## Deployment to Heroku

- Create a heroku app for your application
- Create an AWS S3 bucket to store the passwords in
- Set configuration variables in your Heroku application
  - `AWSAccessKey` Your AWS (IAM) access key
  - `AWSSecretKey` The secret key belonging to the access key
  - `AWSS3Bucket` The bucket name from the second step
  - `passwordSalt` A random unique salt for encrypting the passwords
  - `usernameSalt` A random unique salt for encrypting the usernames
- Deploy the application code to your Heroku app using git

If you don't know what to choose as a salt you can use any password generator you really trust to generate a strong password only you know to generate it. The two salts should not match each other and only you (and your Heroku app) should know them. For example you could generate the salts using `openssl rand -base64 30`.
