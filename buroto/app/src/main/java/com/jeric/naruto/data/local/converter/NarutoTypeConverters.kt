package com.jeric.naruto.data.local.converter

import androidx.room.TypeConverter
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import com.jeric.naruto.data.local.entity.FamilyEntity
import com.jeric.naruto.data.local.entity.PersonalEntity

class NarutoTypeConverters {

    private val gson = Gson()

    @TypeConverter
    fun fromList(list: List<String>): String {
        val type = object : TypeToken<List<String>>() {}.type
        return gson.toJson(list, type)
    }

    @TypeConverter
    fun toList(value: String): List<String> {
        val type = object : TypeToken<List<String>>() {}.type
        return gson.fromJson(value, type)
    }

    @TypeConverter
    fun fromFamilyEntity(status: FamilyEntity?): String {
        return gson.toJson(status)
    }

    @TypeConverter
    fun toFamilyEntity(value: String): FamilyEntity? {
        return gson.fromJson(value, FamilyEntity::class.java)
    }

    @TypeConverter
    fun fromPersonalEntity(status: PersonalEntity?): String {
        return gson.toJson(status)
    }

    @TypeConverter
    fun toPersonalEntity(value: String): PersonalEntity? {
        return gson.fromJson(value, PersonalEntity::class.java)
    }
}