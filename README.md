# WebEngine CMS 1.2.5

WebEngine is an Open source Content Management System (CMS) for Mu Online servers. Our main goal is to provide a fast, secure and high quality framework for server owners to create and implement their own features to their websites.

## Getting Started

These instructions will help you deploy your own website using WebEngine CMS.

### Prerequisites

Here's what you'll need to run WebEngine CMS in your web server

* Apache mod_rewrite
* PHP 7.4 or higher (8.1 recommended)
* PHP modules: PDO dblib/odbc/sqlsrv, cURL, OpenSSL, GD
* PHP short_open_tag enabled

### Installing

1. Download the latest release of WebEngine CMS
2. Upload the ZIP file contents to your web server
3. Run WebEngine CMS Installer by going to `example.com/install` and follow the given instructions
4. Configure the master cron job located at `/includes/cron/cron.php` to run `once per minute`. For more detailed instructions [click here](https://github.com/lautaroangelico/WebEngine/wiki/Setting-up-the-master-cron-job).

## Other Software

WebEngine CMS wouldn't be possible without the following awesome projects.

* [PHPMailer](https://github.com/PHPMailer/PHPMailer/)
* [Bootstrap](https://getbootstrap.com/)
* [jQuery](http://jquery.com/)
* [reCAPTCHA](https://github.com/google/recaptcha)

## Author

* **Lautaro Angelico** - *Developer*

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Support

### WebEngine CMS Official Website
[WebEngine CMS Official Website](https://webenginecms.org/)

### Discord Server
[WebEngine CMS Discord](https://webenginecms.org/discord)