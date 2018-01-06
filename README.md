# Crypto Generator

Quick utility to encrypt/decrypt entities. Only works for 3DES atm.

## Prerequisites

- Php 7.1
- Composer (brew install composer)

## Installation

- Clone the project
- Run `composer install`
- Duplicate `.env.example` and rename to `.env`
- Navigate to the `public` folder
- Start the server here, you can use the internal Php server. The following command starts the server on port 8080:
  `php -S :8080`
	
## Live URL

The API is hosted [here](https://crypto-generator.herokuapp.com)

## Endpoints

[POST] /encrypt_3des
  + request
	{
	    raw: 'dataToEncrypt`
	}
  + response
   	{
	   data: `encryptedData`
	}

[POST] /decrypt_3des
  + request
	{
	   encrypted: `dataToDecrypt`
	}

  + response
	{
	   data: `decryptedData`
	}
