# Time Breaker

## Install for developing:

- Be sure Docker is running
- Run ```make install``` inside project root

## Intall and run test

- Be sure Docker is running
- Run ```make up-build-test``` inside project root

There are 2 endpoints:

1. POST ```/api/v1/time-breaker```
```json
{
    "start_date": "2020-01-01T00:00:00",
    "end_date": "2021-01-01T00:00:00",
    "intervals": ["2m", "m", "3h"]
}
```
result will be
```json
{
    "data": {
        "start_date": "2020-01-01T00:00:00.000000Z",
        "end_date": "2021-01-01T00:00:00.000000Z",
        "units": [
            {
                "intervals": "2m,1m,3h",
                "result": [
                    {
                        "2m": 6
                    },
                    {
                        "1m": 0
                    },
                    {
                        "3h": 0
                    }
                ]
            }
        ]
    }
}
```
2. GET ```/api/v1/time-breaker```
```json
{
    "start_date": "2020-01-01T00:00:00",
    "end_date": "2021-01-01T00:00:00"
}
```
result will be the same as for POST
