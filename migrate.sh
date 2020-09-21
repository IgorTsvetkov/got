mysqladmin -u root -f drop got
mysqladmin -u root -f create got
./yii migrate --migrationPath=@yii/rbac/migrations/ --interactive=0
./yii migrate --interactive=0