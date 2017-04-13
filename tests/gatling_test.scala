package com.myapplication.app

import com.excilys.ebi.gatling.core.Predef._
import com.excilys.ebi.gatling.http.Predef._
import assertions._

class UploadFileScenario extends Simulation {

    val httpConf = httpConfig
        .baseURL("http://5471.190.65:80")
        .acceptHeader("image/png,image/*;q=0.8,*/*;q=0.5")
        .acceptEncodingHeader("gzip, deflate")
        .acceptLanguageHeader("en-US,en;q=0.5")
        .connection("keep-alive")
        .userAgentHeader("Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36")

    val scn = scenario("Access the Website")

    .exec(http("homepage_GET")
        .get("/")
        .header("Content-Type", "text/html")
        )

    setUp(
        scn.users(30).ramp(30).protocolConfig(httpConf)
    )

    assertThat(
        global.failedRequests.count.is(0)
    )