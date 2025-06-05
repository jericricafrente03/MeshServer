package com.jeric.ricafrente.naruto.data.mapper.kara

import com.jeric.ricafrente.naruto.core.database.Kara_table
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.utils.convertStringToList

fun Kara_table.toDomainModel(): KaraModel {
    return KaraModel(
        id = this.id,
        name = this.name,
        images = this.images.convertStringToList(),
        jutsu = this.jutsu.convertStringToList(),
        natureType = this.natureType.convertStringToList(),
        tools = this.tools.convertStringToList(),
        father = this.father,
        mother = this.mother,
        brother = this.brother,
        daughter = this.daughter,
        wife = this.wife,
        birthdate = this.birthdate,
        bloodType = this.bloodType,
        sex = this.sex
    )
}


fun List<Kara_table>.fromEntityToModel(): List<KaraModel> {
    return this.map { it.toDomainModel() }
}
