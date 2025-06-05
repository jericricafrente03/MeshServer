package com.jeric.naruto.data.local.entity

import androidx.room.Entity
import androidx.room.PrimaryKey
import com.jeric.naruto.util.Constants

@Entity(tableName = Constants.TAIL_BEAST_TABLE)
data class TailedBeastEntity(
    @PrimaryKey
    val id: String,
//    val images: List<String>,
//    val jutsu: List<String>,
    val name: String,
//    val natureType: List<String>,
//    val tools: List<String>,
//    val uniqueTraits: List<String>,
)
