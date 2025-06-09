package com.jeric.ricafrente.naruto.core.database.di


import com.jeric.ricafrente.naruto.core.database.createDatabase
import com.jeric.ricafrente.naruto.core.database.dao.AkatsukiDao
import com.jeric.ricafrente.naruto.core.database.dao.ClanDao
import com.jeric.ricafrente.naruto.core.database.dao.KaraDao
import com.jeric.ricafrente.naruto.core.database.dao.NarutoCharacterDao
import com.jeric.ricafrente.naruto.core.database.dao.TailedBeastDao
import com.jeric.ricafrente.naruto.core.database.sqlDriverFactory
import com.jeric.ricafrente.naruto.data.repository.LocalDataSourcesImpl
import com.jeric.ricafrente.naruto.data.repository.LocalDataSourcesInterface
import org.koin.dsl.module


val databaseModule = module {
    factory { sqlDriverFactory() }
    single { createDatabase(driver = get()) }
    single { NarutoCharacterDao(narutoDatabase = get(), client = get()) }
    single { TailedBeastDao(tailedBeastDatabase = get()) }
    single { AkatsukiDao(akatsukiDatabase = get()) }
    single { ClanDao(clanDatabase = get()) }
    single { KaraDao(karaDatabase = get()) }
    single<LocalDataSourcesInterface> { LocalDataSourcesImpl(get(), get(), get(),get(),get()) }

}