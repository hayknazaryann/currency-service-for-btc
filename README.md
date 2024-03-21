# Installation

### Clone the repository
```
git clone https://github.com/hayknazaryann/currency-service-for-btc.git
```

### Switch to the repo folder
```
cd currency-service-for-btc
```

### Install all the dependencies using composer
```
composer install
```

### Copy the example env file and make the required configuration changes in the .env file
```
cp .env.example .env
```

### Set the API URL and given token
```
BLOCKCHAIN_API=<url>
BLOCKCHAIN_API_TOKEN=<token>
```

### Generate a new application key
```
php artisan key:generate
```

### Start the local development server
```
php artisan serve
```

# Currency Exchange API

This API allows you to fetch currency exchange rates and perform currency conversions with Bitcoin (BTC) as the base currency.

## Getting Started

To use this API, you need to have a valid authorization token. Include the token in the request headers as follows:

```
Authorization: Bearer <your_token_here>
Replace `<your_token_here>` with your actual authorization token.
```

# Endpoints

## Get Exchange Rates

- **URL:** `/api/v1/rates`
- **Method:** GET
- **Description:** Fetches the latest currency exchange rates.

#### Request Parameters

- `currency`: The currency code to convert to (e.g., USD).

#### Response

```json
{
    "success": true,
    "rates": {
        
    }
}
```

#### CURL Example
```
curl --location 'your.domain/api/v1/rates' \
--header 'Authorization: <your_token_here>'
```



## Convert Currency

- **URL:** `/api/v1/convert`
- **Method:** POST
- **Description:** Converts an amount from one currency to another, including a 2% commission for currency exchange.

#### Request Parameters

- `currency_from`: The currency code to convert from (e.g., BTC).
- `currency_to`: The currency code to convert to (e.g., USD).
- `value`: The amount to convert.

#### Request Body Example

```
{
    "currency_from": "BTC",
    "currency_to": "USD",
    "value": 1
}
```

#### Response

```
{
    "status": "success",
    "code": 200,
    "data": {
        "currency_from": "BTC",
        "currency_to": "USD",
        "value": "1",
        "converted_value": 65869.82,
        "rate": 66535.17
    }
}
```

#### CURL Example
```
curl --location 'your.domain/api/v1/convert' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <your_token_here>' \
--form 'currency_from="BTC"' \
--form 'currency_to="USD"' \
--form 'value="1"'
```

