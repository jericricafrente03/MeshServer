package com.jeric.ricafrente.naruto.core.network.di

import com.jeric.ricafrente.naruto.core.network.client.NarutoClient
import com.jeric.ricafrente.naruto.core.network.createHttpClient
import com.jeric.ricafrente.naruto.core.network.helper.NarutoApi
import org.koin.core.module.Module
import org.koin.dsl.module

val networkModule: (enableLogging: Boolean) -> Module get() = { enableLogging ->
    module {
        single { createHttpClient(enableLogging) }
        single<NarutoApi> { NarutoClient(get()) }
    }
}