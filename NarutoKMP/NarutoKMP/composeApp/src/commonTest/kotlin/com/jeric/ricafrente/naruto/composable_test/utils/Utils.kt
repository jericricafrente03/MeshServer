package com.jeric.ricafrente.naruto.composable_test.utils

import com.jeric.ricafrente.naruto.core.model.akatsuki.AkatsukiModel
import com.jeric.ricafrente.naruto.core.model.buruto.KaraModel
import com.jeric.ricafrente.naruto.core.model.character.CharacterModel
import com.jeric.ricafrente.naruto.core.model.clan.ClanModel
import com.jeric.ricafrente.naruto.core.model.tailedbeast.TailedBeastModel


fun getTailedBeast(): List<TailedBeastModel> {
    return listOf(
        TailedBeastModel(
            id = 1,
            images = listOf(),
            jutsu = listOf(),
            name = "Kurama",
            natureType = listOf(),
            tools = listOf(),
            uniqueTraits = listOf()
        ),
        TailedBeastModel(
            id = 2,
            images = listOf(),
            jutsu = listOf(),
            name = "Naruto",
            natureType = listOf(),
            tools = listOf(),
            uniqueTraits = listOf()
        ),
        TailedBeastModel(
            id = 3,
            images = listOf(),
            jutsu = listOf(),
            name = "Sasuke",
            natureType = listOf(),
            tools = listOf(),
            uniqueTraits = listOf()
        ),
        TailedBeastModel(
            id = 3,
            images = listOf(),
            jutsu = listOf(),
            name = "Hinata",
            natureType = listOf(),
            tools = listOf(),
            uniqueTraits = listOf()
        ),
        TailedBeastModel(
            id = 4,
            images = listOf(),
            jutsu = listOf(),
            name = "Kakashi",
            natureType = listOf(),
            tools = listOf(),
            uniqueTraits = listOf()
        ),
    )
}

fun clanList(): List<ClanModel> {
    return listOf(
        ClanModel(
            characters = listOf(1, 2, 3),
            id = "101",
            name = "Uchiha"
        ),
        ClanModel(
            characters = listOf(4, 5),
            id = "102",
            name = "Hyuga"
        ),
        ClanModel(
            characters = listOf(6),
            id = "103",
            name = "Nara"
        ),
        ClanModel(
            characters = listOf(7, 8, 9),
            id = "104",
            name = "Akimichi"
        ),
        ClanModel(
            characters = listOf(10, 11),
            id = "105",
            name = "Yamanaka"
        )
    )
}

fun akatsukiList(): List<AkatsukiModel> {
    return listOf(
        AkatsukiModel(
            father = "Fugaku", mother = "Mikoto", brother = null, wife = "none", daughter = null,
            id = "1",
            images = listOf("itachi1.png", "itachi2.png"),
            jutsu = listOf("Amaterasu", "Tsukuyomi", "Susanoo"),
            name = "Itachi Uchiha",
            natureType = listOf("Fire", "Water", "Yin Release"),
            birthdate = "June 9", sex = "Male", bloodType = "AB",
            tools = listOf("Kunai", "Shuriken")
        ),
        AkatsukiModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "2",
            images = listOf("kisame1.png"),
            jutsu = listOf("Water Prison", "Shark Bomb"),
            name = "Kisame Hoshigaki",
            natureType = listOf("Water"),
            birthdate = "March 18", sex = "Male", bloodType = "AB",
            tools = listOf("Samehada")
        ),
        AkatsukiModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "3",
            images = listOf("pain1.png", "pain2.png"),
            jutsu = listOf("Almighty Push", "Chibaku Tensei"),
            name = "Pain (Nagato)",
            natureType = listOf("Rinnegan", "All 5 basic elements"),
            birthdate = "September 19", sex = "Male", bloodType = "A",
            tools = listOf("Black Receivers")
        ),
        AkatsukiModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "4",
            images = listOf("konan1.png"),
            jutsu = listOf("Paper Jutsu", "Dance of the Shikigami"),
            name = "Konan",
            natureType = listOf("Paper", "Water"),
            birthdate = "February 20", sex = "Female", bloodType = "O",
            tools = listOf("Paper Bombs")
        ),
        AkatsukiModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "5",
            images = listOf("deidara1.png"),
            jutsu = listOf("Explosive Clay", "C0"),
            name = "Deidara",
            natureType = listOf("Earth", "Explosion Release"),
            birthdate = "May 5", sex = "Male", bloodType = "AB",
            tools = listOf("Clay Pouch")
        )
    )
}


fun karaList(): List<KaraModel> {
    return listOf(
        KaraModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "1",
            images = listOf("jigen1.png", "jigen2.png"),
            jutsu = listOf("Sukunahikona", "Daikokuten"),
            name = "Jigen / Isshiki Ōtsutsuki",
            natureType = listOf("Fire", "Space-Time"),
            birthdate = "Unknown", sex = "Male", bloodType = "Unknown",
            tools = listOf("Kāma", "Shrinking Ability")
        ),
        KaraModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "2",
            images = listOf("kawaki1.png"),
            jutsu = listOf("Kāma Abilities", "Modified Body Techniques"),
            name = "Kawaki",
            natureType = listOf("Fire", "Earth"),
            birthdate = "January 1", sex = "Male", bloodType = "O",
            tools = listOf("Kāma", "Body Implants")
        ),
        KaraModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "3",
            images = listOf("delta1.png"),
            jutsu = listOf("Laser Beam", "Regeneration"),
            name = "Delta",
            natureType = listOf("Unknown"),
            birthdate = "Unknown", sex = "Female", bloodType = "Unknown",
            tools = listOf("Scientific Ninja Tools", "Drones")
        ),
        KaraModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "4",
            images = listOf("code1.png"),
            jutsu = listOf("White Kāma", "Claw Marks"),
            name = "Code",
            natureType = listOf("Unknown"),
            birthdate = "Unknown", sex = "Male", bloodType = "Unknown",
            tools = listOf("Modified Body", "Teleportation via Claw Marks")
        ),
        KaraModel(
            father = "Unknown", mother = "Unknown", brother = null, wife = "none", daughter = null,
            id = "5",
            images = listOf("borou1.png"),
            jutsu = listOf("Virus Release", "Lava Style"),
            name = "Boro",
            natureType = listOf("Lava", "Water"),
            birthdate = "Unknown", sex = "Male", bloodType = "Unknown",
            tools = listOf("Scientific Ninja Tool Core", "Virus Canisters")
        )
    )
}

fun characterList(): List<CharacterModel> {
    return listOf(
        CharacterModel(
            id = "1",
            name = "Naruto Uzumaki",
            images = listOf("naruto1.png", "naruto2.png"),
            father = "Minato Namikaze",
            mother = "Kushina Uzumaki",
            brother = null,
            daughter = "Himawari Uzumaki",
            wife = "Hinata Hyuga",
            jutsu = listOf("Rasengan", "Shadow Clone Jutsu", "Sage Mode", "Kurama Mode"),
            natureType = listOf("Wind", "Earth", "Lava", "Magnet", "Boil"),
            birthdate = "October 10",
            bloodType = "B",
            sex = "Male",
            tools = listOf("Kunai", "Scroll", "Shuriken")
        ),
        CharacterModel(
            id = "2",
            name = "Sasuke Uchiha",
            images = listOf("sasuke1.png", "sasuke2.png"),

            father = "Fugaku Uchiha",
            mother = "Mikoto Uchiha",
            brother = "Itachi Uchiha",
            daughter = "Sarada Uchiha",
            wife = "Sakura Haruno",
            jutsu = listOf("Chidori", "Amaterasu", "Susanoo", "Rinnegan Techniques"),
            natureType = listOf("Fire", "Lightning", "Yin", "Deva Path"),

            birthdate = "July 23",
            bloodType = "AB",
            sex = "Male",
            tools = listOf("Sword", "Shuriken")
        ),
        CharacterModel(
            id = "3",
            name = "Sakura Haruno",
            images = listOf("sakura1.png"),

            father = null,
            mother = null,
            brother = null,
            daughter = "Sarada Uchiha",
            wife = null,
            jutsu = listOf("Chakra Enhanced Strength", "Medical Ninjutsu"),
            natureType = listOf("Earth", "Water"),

            birthdate = "March 28",
            bloodType = "O",
            sex = "Female",
            tools = listOf("Medical Kit", "Kunai")
        ),
        CharacterModel(
            id = "4",
            name = "Kakashi Hatake",
            images = listOf("kakashi1.png"),

            father = "Sakumo Hatake",
            mother = null,
            brother = null,
            daughter = null,
            wife = null,
            jutsu = listOf("Lightning Blade", "Kamui", "Sharingan Techniques"),
            natureType = listOf("Lightning", "Water", "Fire", "Earth", "Wind"),

            birthdate = "September 15",
            bloodType = "O",
            sex = "Male",
            tools = listOf("Book", "Kunai", "Mask")
        )
    )
}