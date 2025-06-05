package com.jeric.ricafrente.naruto.data.mapper.kara

import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.network.model.akatsuki.AkatsukiDto
import com.jeric.ricafrente.naruto.core.network.model.buruto.KaraDto
import com.jeric.ricafrente.naruto.data.mapper.akatsuki.toDomainModel


fun KaraDto.toDomainModel(): KaraModel {
    return KaraModel(
        id = this.id.toString(),
        name = this.name,
        images = this.images,
        jutsu = this.jutsu,
        natureType = this.natureType,
        tools = this.tools,
        father = this.family?.father,
        mother = this.family?.mother,
        brother = this.family?.brother,
        daughter = this.family?.daughter,
        wife = this.family?.wife,
        birthdate = this.personal?.birthdate,
        bloodType = this.personal?.bloodType,
        sex = this.personal?.sex
    )
}

fun List<KaraDto>.toKaraModels(): List<KaraModel> {
    return this.map { it.toDomainModel() }
}










