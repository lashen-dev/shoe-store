<?php
// بدء الجلسة
session_start();

// تضمين ملف الإعدادات (الاتصال بقاعدة البيانات)
require_once 'config.php';

// تضمين ملف التوجيهات
require_once 'routes.php';