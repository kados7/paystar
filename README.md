#### **برای احراز هویت از لاراول پاسپورت استفاده شده است . حتما passport:install انجام شود.**

### برای استفاده :

    php artisan migrate
    php artisan passport:install
    php artisan db:seed
    
### در فایل env مقادیر زیر مشخص شود :
    PAYSTAR_CREATE_URL = "https://core.paystar.ir/api/pardakht/create"
    PAYSTAR_VERIFY_URL = "https://core.paystar.ir/api/pardakht/verify"
    PAYSTAR_GATEWAY_ID = "***********"
    PAYSTAR_SIGN_KEY = "**************************************"

---

### 
ویژگی های به کار رفته
- Laravel API
- Front = VueJs and Pinia
- Laravel Test
- Design Pattern (strategy , chain of responsibility)
- Laravel Passport Authentication (JWT)



