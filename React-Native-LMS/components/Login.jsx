import axios from 'axios';
import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, ImageBackground, Alert } from 'react-native';
import { Image } from 'react-native';
import { AsyncStorage } from 'react-native';
import { createStackNavigator } from '@react-navigation/stack';
import { NavigationContainer } from '@react-navigation/native';
// import Attendance from './Attendance';

const Login=({navigation})=>  {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [errorMessage, setErrorMessage] = useState("");

  const handleEmailChange = (text) => {
    setEmail(text);
  };

  const handlePasswordChange = (text) => {
    setPassword(text);
  };

  const handleSubmit = async () => {
   
    
    try {
      const response = await fetch("http://192.168.0.105:8000/api/userLMS/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email,
          password,
        }),
      });

      const data = await response.json();
      {console.log(data)}
      if (response.ok) {
      
        Alert.alert("Login successful", "", [{
          text: "OK",
          onPress: () => {
            navigation.navigate("Attendance");
          }
        }]);
        
      } else {
        Alert.alert("Login failed", data.message);
      }
    } catch (error) {
      console.error(error);
      Alert.alert("Internet Error")
    }
  };

  useEffect(() => {
    if (errorMessage) {
      const timeoutId = setTimeout(() => {
        setErrorMessage("");
      }, 2000);
      return () => clearTimeout(timeoutId);
    }
  }, [errorMessage]);

  return (
    
      <View style={styles.container}>
      <Image
  source={require('./imagess/imageee.png')}
  style={{ marginTop:450,width: 150, height: 150, borderRadius: 100}}
  resizeMode="contain"
/>

        <Text style={styles.titles}>Login</Text>
        {errorMessage ? <Text style={styles.error}>{errorMessage}</Text> : null}
        <TextInput
          style={styles.input}
          placeholder="Email"
          onChangeText={handleEmailChange}
          value={email}
        />
        <TextInput
          style={styles.input}
          placeholder="Password"
          secureTextEntry={true}
          onChangeText={handlePasswordChange}
          value={password}
        />
        <TouchableOpacity style={styles.button} onPress={handleSubmit}>
          <Text style={styles.buttonText}>Login</Text>
        </TouchableOpacity>
      </View>
   
  );
};

const styles = StyleSheet.create({
 

  container: {
    flex: 0,
    alignItems: 'center',
    justifyContent: 'center',
    height:800
  },

  titles: {
    fontSize: 30,
    fontWeight:'bold',
    color:'blue',
    
    height:50
  },
  input: {
    width: 250,
    height: 50,
    margin: 5,
    padding: 10,
    borderWidth: 1,
    borderRadius: 5,
    color: 'black',
    borderWidth:1,
    borderColor:'blue',
  },
  button: {
    width: 200,
    height: 50,
    marginTop:10,
    backgroundColor: 'blue',
    borderRadius: 5,
    alignItems: 'center',
    justifyContent: 'center',
  },
  buttonText: {
    color: '#ffffff',
    fontSize: 18,
  },
  error: {
    color: 'red',
    marginBottom: 10,
  },
});

export default Login;
