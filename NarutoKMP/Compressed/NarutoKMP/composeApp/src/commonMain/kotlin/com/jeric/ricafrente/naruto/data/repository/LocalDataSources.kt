package com.jeric.ricafrente.naruto.data.repository

import com.jeric.ricafrente.naruto.core.database.dao.AkatsukiDao
import com.jeric.ricafrente.naruto.core.database.dao.ClanDao
import com.jeric.ricafrente.naruto.core.database.dao.KaraDao
import com.jeric.ricafrente.naruto.core.database.dao.NarutoCharacterDao
import com.jeric.ricafrente.naruto.core.database.dao.TailedBeastDao

data class LocalDataSources(
    val characterDao: NarutoCharacterDao,
    val tailBeastDao: TailedBeastDao,
    val clanDao: ClanDao,
    val akatsukiDao: AkatsukiDao,
    val karaDao: KaraDao
)