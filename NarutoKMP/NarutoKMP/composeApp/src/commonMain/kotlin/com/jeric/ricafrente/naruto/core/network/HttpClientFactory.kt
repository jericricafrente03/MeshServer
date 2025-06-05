package com.jeric.ricafrente.naruto.core.network

import io.ktor.client.HttpClient

//Todo Need to check if removable or not
expect fun createPlatformHttpClient(): HttpClient