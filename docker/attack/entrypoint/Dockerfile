FROM microsoft/dotnet:2.2-sdk AS builder

WORKDIR /app

COPY ./ .

RUN dotnet publish ./cmd/attack/entrypoint/entrypoint.sln -c Release -o /out

FROM scratch
FROM microsoft/dotnet:2.2-aspnetcore-runtime

WORKDIR /app

COPY --from=builder /out/ .

ENTRYPOINT ["dotnet", "entrypoint.dll"]