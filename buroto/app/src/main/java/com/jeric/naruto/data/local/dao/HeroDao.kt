package com.jeric.naruto.data.local.dao

import androidx.paging.PagingSource
import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.jeric.naruto.data.local.entity.CharacterEntity

@Dao
interface HeroDao {
    @Query("SELECT * FROM images_table")
    fun getAllHeroes(): PagingSource<Int, CharacterEntity>

    @Query("SELECT * FROM images_table WHERE id=:heroId")
    fun getSelectedHero(heroId: Int): CharacterEntity

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun addHeroes(heroes: List<CharacterEntity>)

    @Query("DELETE FROM images_table")
    suspend fun deleteAllHeroes()
}