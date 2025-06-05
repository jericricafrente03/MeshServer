package com.jeric.naruto.data.local.dao

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.jeric.naruto.data.local.entity.TailedBeastEntity

@Dao
interface TailBeastDao {


    @Query("SELECT * FROM tail_beast_table")
    fun getTailBeast(): List<TailedBeastEntity>

    @Query("SELECT * FROM tail_beast_table WHERE id=:beast")
    fun getSelectedTailBeast(beast: Int): TailedBeastEntity

    @Insert(onConflict = OnConflictStrategy.REPLACE)
    suspend fun addBeast(heroes: List<TailedBeastEntity>)

    @Query("DELETE FROM tail_beast_table")
    suspend fun deleteBeast()


}