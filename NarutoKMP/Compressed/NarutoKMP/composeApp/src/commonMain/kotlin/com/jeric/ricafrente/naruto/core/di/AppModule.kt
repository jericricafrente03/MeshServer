package com.jeric.ricafrente.naruto.core.di

import com.jeric.ricafrente.naruto.core.database.di.databaseModule
import com.jeric.ricafrente.naruto.core.network.di.networkModule
import com.jeric.ricafrente.naruto.core.utils.preferenceModule
import com.jeric.ricafrente.naruto.data.di.dataModule
import org.koin.core.context.startKoin
import org.koin.dsl.KoinAppDeclaration

fun initKoin(enableNetworkLogs: Boolean = false, appDeclaration: KoinAppDeclaration = {}) =
    startKoin {
        appDeclaration()
        modules(
            databaseModule,
            networkModule(enableNetworkLogs),
            dataModule,
            preferenceModule
        )
    }