package com.jeric.narutoapp.di

import android.app.Application
import androidx.room.Room
import com.jeric.narutoapp.data.api.NarutoApi
import com.jeric.narutoapp.data.local.NarutoDatabase
import com.jeric.narutoapp.data.repository.CharacterRepository
import dagger.Module
import dagger.Provides
import dagger.hilt.InstallIn
import dagger.hilt.components.SingletonComponent
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import javax.inject.Singleton

@Module
@InstallIn(SingletonComponent::class)
object AppModule {

    @Provides
    @Singleton
    fun provideNarutoApi(): NarutoApi {
        return Retrofit.Builder()
            .baseUrl("https://dattebayo-api.onrender.com/")
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(NarutoApi::class.java)
    }

    @Provides
    @Singleton
    fun provideNarutoDatabase(app: Application): NarutoDatabase {
        return Room.databaseBuilder(
            app,
            NarutoDatabase::class.java,
            "naruto.db"
        ).build()
    }

    @Provides
    @Singleton
    fun provideCharacterRepository(
        api: NarutoApi,
        db: NarutoDatabase
    ): CharacterRepository {
        return CharacterRepository(api, db)
    }
} 