package com.jeric.narutoapp.data.model

import androidx.room.Entity
import androidx.room.PrimaryKey
import com.google.gson.annotations.SerializedName

@Entity(tableName = "characters")
data class NarutoCharacter(
    @PrimaryKey
    val id: String,
    val name: String,
    val images: List<String>,
    val personal: Personal,
    val rank: Rank,
    val jutsu: List<String>,
    val natureType: List<String>,
    val tools: List<String>,
    val uniqueTraits: List<String>,
    val voiceActors: VoiceActors,
    val family: Family,
    val clan: String?,
    val kekkeiGenkai: List<String>,
    val classification: List<String>,
    val status: String
)

data class Personal(
    val birthdate: String,
    val sex: String,
    val age: Age,
    val height: Height,
    val weight: Weight,
    val bloodType: String,
    val occupation: List<String>,
    val affiliation: List<String>,
    val team: List<String>
)

data class Age(
    val partI: String?,
    val partII: String?,
    val academyGraduate: String?,
    val chuninPromotion: String?
)

data class Height(
    val partI: String?,
    val partII: String?,
    val gaiden: String?
)

data class Weight(
    val partI: String?,
    val partII: String?
)

data class Rank(
    val ninjaRank: NinjaRank,
    val ninjaRegistration: String
)

data class NinjaRank(
    val partI: String?,
    val partII: String?,
    val blankPeriod: String?,
    val borutoManga: String?
)

data class VoiceActors(
    val japanese: List<String>,
    val english: List<String>
)

data class Family(
    val father: String?,
    val mother: String?,
    val brother: List<String>,
    val sister: List<String>,
    val spouse: String?,
    val son: List<String>,
    val daughter: List<String>,
    val grandson: List<String>,
    val granddaughter: List<String>,
    val cousin: List<String>,
    val uncle: List<String>,
    val nephew: List<String>,
    val niece: List<String>,
    val grandfather: List<String>,
    val grandmother: List<String>
) 