package com.jeric.unplash.data.local

import androidx.room.Database
import androidx.room.RoomDatabase
import com.jeric.unplash.data.local.entity.UnsplashImageEntity
import com.jeric.unplash.data.local.entity.UnsplashRemoteKeys

@Database(
    entities = [UnsplashImageEntity::class, UnsplashRemoteKeys::class],
    version = 1,
    exportSchema = false
)
abstract class UnSplashDatabase: RoomDatabase() {
    abstract fun editorialFeedDao(): UnsplashDao
}