
import { StyleSheet, Text, View } from 'react-native';
import React from 'react';
import Login from './components/Login';
 import Attendance from './components/Attendance';
import { createStackNavigator } from '@react-navigation/stack';
import { NavigationContainer } from '@react-navigation/native';

export default function App() {
  const Stack = createStackNavigator();
  return (
     <NavigationContainer>
      <Stack.Navigator>
          <Stack.Screen name="Login" component={Login} />   
         <Stack.Screen name="Attendance" component={Attendance} /> 
      </Stack.Navigator>
    </NavigationContainer>

    
  );
 
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
