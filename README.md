# WebEngine CMS 1.2.1

Open source Content Management System for Mu Online servers. WebEngine's goal is to provide a fast and secure framework for server owners to create and implement their own features to the CMS.

## Getting Started

These instructions will help you deploy your own copy of the CMS.

### Prerequisites

Here's what you need to run WebEngine CMS

* Apache mod_rewrite
* PHP 5.6 or higher
* PHP PDO dblib/odbc/sqlsrv
* cURL Extension
* OpenSSL Extension
* short_open_tag enabled
* JSON

### Installing

1. Upload and extract the release files to your web server
2. Run WebEngine CMS Installer `yourwebsite.com/install` and follow the instructions
3. Add the master cron job `/includes/cron/cron.php` to run `once per minute`

## Other Software

WebEngine CMS wouldn't be possible without the following awesome projects.

* [PHPMailer](https://github.com/PHPMailer/PHPMailer/)
* [Bootstrap](https://getbootstrap.com/)
* [jQuery](http://jquery.com/)
* [reCAPTCHA](https://github.com/google/recaptcha)

## Authors

* **Lautaro Angelico** - *Developer*
* **Mon** - *Developer*

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Support

### Official Discord Server
[WebEngine CMS Official Discord](https://webenginecms.org/discord)

### WebEngine Community Support Forum
[WebEngine Support Forum](https://forum.webenginecms.org/)
