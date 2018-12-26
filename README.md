# Scoring Demo Web Interface
Web application that allows to display scoring configuration
Written using Yii2 and PHP 7.2. Requires PostgreSQL 10
Should be run using Docker.

## Running application
1. Copy [.env.example](./.env.example) to `.env` and put settings
2. Execute serve:
```bash
php yii serve
```

## Console Commands
### Users
1. Creating user
```bash
php yii users/create
```
2. Deleting user
```bash
php yii users/delete login
```
3. Getting Google Authenticator url
```bash
php yii users/link login
```
4. Getting Google Authenticator code
```bash
php yii users/code login
```

## Author
- [Alexander <horat1us> Letnikow](mailto:reclamme@gmail.com)
