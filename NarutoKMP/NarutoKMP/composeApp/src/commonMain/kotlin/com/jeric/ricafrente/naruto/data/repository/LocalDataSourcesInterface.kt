package com.jeric.ricafrente.naruto.data.repository

import com.jeric.ricafrente.naruto.core.database.dao.AkatsukiDao
import com.jeric.ricafrente.naruto.core.database.dao.ClanDao
import com.jeric.ricafrente.naruto.core.database.dao.KaraDao
import com.jeric.ricafrente.naruto.core.database.dao.NarutoCharacterDao
import com.jeric.ricafrente.naruto.core.database.dao.TailedBeastDao

interface LocalDataSourcesInterface {
    fun characterDao(): NarutoCharacterDao
    fun tailBeastDao(): TailedBeastDao
    fun clanDao(): ClanDao
    fun akatsukiDao(): AkatsukiDao
    fun karaDao(): KaraDao
}