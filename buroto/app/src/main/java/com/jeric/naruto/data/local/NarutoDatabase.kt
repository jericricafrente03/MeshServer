package com.jeric.naruto.data.local

import androidx.room.Database
import androidx.room.RoomDatabase
import androidx.room.TypeConverters
import com.jeric.naruto.data.local.converter.NarutoTypeConverters
import com.jeric.naruto.data.local.dao.HeroDao
import com.jeric.naruto.data.local.dao.HeroRemoteKeysDao
import com.jeric.naruto.data.local.dao.TailBeastDao
import com.jeric.naruto.data.local.entity.CharacterEntity
import com.jeric.naruto.data.local.entity.TailedBeastEntity
import com.jeric.naruto.domain.model.remote_key.HeroRemoteKeys

@Database(
    entities = [
        CharacterEntity::class,
//        TailedBeastEntity::class,
        HeroRemoteKeys::class
    ],
    version = 1,
    exportSchema = false
)
@TypeConverters(NarutoTypeConverters::class)
abstract class NarutoDatabase : RoomDatabase() {
    abstract fun heroDao(): HeroDao
//    abstract fun tailBeastDao(): TailBeastDao
    abstract fun heroRemoteKeysDao(): HeroRemoteKeysDao
}