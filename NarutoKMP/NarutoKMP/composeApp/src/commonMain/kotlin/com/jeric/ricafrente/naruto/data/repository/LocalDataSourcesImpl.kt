package com.jeric.ricafrente.naruto.data.repository

import com.jeric.ricafrente.naruto.core.database.dao.AkatsukiDao
import com.jeric.ricafrente.naruto.core.database.dao.ClanDao
import com.jeric.ricafrente.naruto.core.database.dao.KaraDao
import com.jeric.ricafrente.naruto.core.database.dao.NarutoCharacterDao
import com.jeric.ricafrente.naruto.core.database.dao.TailedBeastDao

class LocalDataSourcesImpl(
    private val characterDao: NarutoCharacterDao,
    private val tailBeastDao: TailedBeastDao,
    private val clanDao: ClanDao,
    private val akatsukiDao: AkatsukiDao,
    private val karaDao: KaraDao
): LocalDataSourcesInterface{
    override fun characterDao(): NarutoCharacterDao {
        return characterDao
    }

    override fun tailBeastDao(): TailedBeastDao {
        return tailBeastDao
    }

    override fun clanDao(): ClanDao {
       return clanDao
    }

    override fun akatsukiDao(): AkatsukiDao {
        return akatsukiDao
    }

    override fun karaDao(): KaraDao {
        return karaDao
    }

}