from api_factory import ApiFactory


class Udemy(ApiFactory):
    def __init__(
        self,
        *args,
        **kwargs,
    ) -> None:
        super().__init__(
            *args,
            **kwargs,
        )

    @property
    def config_key(self) -> str:
        return "udemy"
