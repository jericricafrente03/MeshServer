package com.jeric.narutoapp.data.local

import androidx.paging.PagingSource
import androidx.room.Dao
import androidx.room.Database
import androidx.room.Entity
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.PrimaryKey
import androidx.room.Query
import androidx.room.RoomDatabase
import com.jeric.narutoapp.data.model.NarutoCharacter

@Database(
    entities = [NarutoCharacter::class],
    version = 1,
    exportSchema = false
)
abstract class NarutoDatabase : RoomDatabase() {
    abstract fun characterDao(): CharacterDao
}

@Dao
interface CharacterDao {
    @Query("SELECT * FROM characters")
    fun getAllCharacters(): PagingSource<Int, NarutoCharacter>

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun insertAll(characters: List<NarutoCharacter>)

    @Query("DELETE FROM characters")
    suspend fun clearAll()
} 