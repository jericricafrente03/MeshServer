package com.jeric.ricafrente.naruto.core.model.character

data class CharacterModel(
    val id: String,
    val name: String,
    val images: List<String>?,
    val jutsu: List<String>?,
    val natureType: List<String>?,
    val tools: List<String>?,

    val father: String?,
    val mother: String?,
    val brother: String?,
    val daughter: String?,
    val wife: String?,

    val birthdate: String?,
    val bloodType: String?,
    val sex: String?
)