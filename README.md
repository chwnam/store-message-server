# Store Message Server

메시지 저장 API 서버

전달받은 해당 메시지를 저장하기만 하는, 초간단 API 서버.

## Endpoint

`/index.php`: 메시지를 저장한다.

- Method: POST
- Parameter:
  - `message`: 필수. 문자열.
  - `tag`: 옵션. 영소문자와 숫자, 언더바, 하이픈만 허용.

## 인증

헤더에 `X-SMS-API-KEY`로 지정된 토큰을 보낸다.
이 값이 맞지 않으면 403 forbidden 응답.


## 설정

`env.dist` 파일에 필요한 설명을 해 두었습니다.
이 파일을 복사하여 `.env`를 만들어 주세요.
필수라고 지정된 값은 반드시 있어야 합니다.

    