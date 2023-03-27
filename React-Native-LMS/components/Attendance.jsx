import React from "react";
import {
  View,
  Text,
  SafeAreaView,
  TouchableOpacity,
  Alert,
} from "react-native";
import { RadioButton } from "react-native-paper";
import { StyleSheet } from "react-native";

import { useState, useEffect } from "react";
import axios from "axios";
import { Dropdown } from "react-native-element-dropdown";
import AntDesign from "@expo/vector-icons/AntDesign";
import AsyncStorage from "@react-native-async-storage/async-storage";

function Attendance({ navigation }) {
  const [value, setValue] = useState(null);
  const [isFocus, setIsFocus] = useState(false);
  const [value2, setValue2] = useState(null);
  const [isFocus2, setIsFocus2] = useState(false);
  const [data, setData] = useState([]);
  const [section, setSection] = useState([]);
  const [students, setStudents] = useState([]);
  const [selectedLevel, setSelectedLevel] = useState("");
  const [selectedSection, setSelectedSection] = useState("");
  const [checked, setChecked] = React.useState("");

  const handleLogout = () => {
    Alert.alert("Do you want to logout", "", [{
      text: "OK",
      onPress: () => {
        navigation.navigate("Login");
      }
    }]);
    
  };

  useEffect(() => {
    fetchData();
    fetchSection();
  }, []);

  const fetchData = async () => {
    try {
      const response = await fetch("http://192.168.0.109:8000/api/levels");
      const json = await response.json();
      // assuming the response is an array of objects with 'label' and 'value' properties
      setData(
        json.map((item) => ({ label: item.levelName, value: item.levelName }))
      );
    } catch (error) {
      console.error(error);
    }
  };
  const fetchSection = async () => {
    try {
      const response = await fetch("http://192.168.0.109:8000/api/sections");
      const json = await response.json();
      // assuming the response is an array of objects with 'label' and 'value' properties
      setSection(
        json.map((item) => ({
          label2: item.sectionName,
          value2: item.sectionName,
        }))
      );
    } catch (error) {
      console.error(error);
    }
  };

  const handleGetStudent = (sectionName) => {
    console.log(sectionName);
    console.log(selectedLevel); // access selectedLevel state here

    axios
      .get(
        `http://192.168.0.109:8000/api/listStudent/${selectedLevel}/${sectionName}`
      )
      .then((response) => {
        setStudents(response.data);
        setSelectedSection(sectionName);
      })
      .catch((error) => {
        console.log(error);
      });
  };

  const renderLabel = () => {
    if (value || isFocus) {
      return (
        <Text style={[styles.label, isFocus && { color: "blue" }]}>
          Select Grade
        </Text>
      );
    }
    return null;
  };

  const renderLabel2 = () => {
    if (value2 || isFocus2) {
      return (
        <Text style={[styles.label2, isFocus2 && { color: "blue" }]}>
          Select Section
        </Text>
      );
    }
    return null;
  };

  const [attendanceStatus, setAttendanceStatus] = useState([]);
  const handleStatus = async (id, status) => {
    console.log(id);
    console.log(status);

    try {
      const response = await axios.post(
        `http://192.168.0.109:8000/api/attendance/createAttendance`,
        {
          studentId: id,
          status: status,
        }
      );
      console.log(response.data);
      setAttendanceStatus(`${status} recorded for student ${id}`);
      setButtonStatus((prevState) => ({ ...prevState, [id]: status }));

      console.log(response.data);
    } catch (error) {
      console.log(error);
    }
  };
  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>Attendance</Text>
        <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
          <Text style={styles.logoutButtonText}>Logout</Text>
        </TouchableOpacity>
      </View>

      <View style={styles.Container}>
        {renderLabel()}
        <Dropdown
          style={[styles.dropdown, isFocus && { borderColor: "blue" }]}
          placeholderStyle={styles.placeholderStyle}
          selectedTextStyle={styles.selectedTextStyle}
          inputSearchStyle={styles.inputSearchStyle}
          iconStyle={styles.iconStyle}
          data={data}
          search
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder={!isFocus ? "Select item" : "..."}
          searchPlaceholder="Search..."
          value={value}
          onFocus={() => setIsFocus(true)}
          onBlur={() => setIsFocus(false)}
          onChange={(item) => {
            setValue(item.value);
            setIsFocus(false);
            setSelectedLevel(item.value); // set selected level here
          }}
          renderLeftIcon={() => (
            <AntDesign
              style={styles.icon}
              color={isFocus ? "blue" : "black"}
              name="Safety"
              size={20}
            />
          )}
        />
      </View>
      <View style={styles.Container}>
        {renderLabel2()}
        <Dropdown
          style={[styles.dropdown, isFocus2 && { borderColor: "blue" }]}
          placeholderStyle={styles.placeholderStyle}
          selectedTextStyle={styles.selectedTextStyle}
          inputSearchStyle={styles.inputSearchStyle}
          iconStyle={styles.iconStyle}
          data={section}
          search
          maxHeight={300}
          labelField="label2"
          valueField="value2"
          placeholder={!isFocus2 ? "Select item" : "..."}
          searchPlaceholder="Search..."
          value={value2}
          onFocus={() => setIsFocus2(true)}
          onBlur={() => setIsFocus2(false)}
          onChange={(item) => {
            setValue2(item.value2);
            setIsFocus2(false);
            handleGetStudent(item.value2);
          }}
          renderLeftIcon={() => (
            <AntDesign
              style={styles.icon}
              color={isFocus ? "blue" : "black"}
              name="Safety"
              size={20}
            />
          )}
        />
      </View>
      {students.map((student) =>
        student ? (
          <View style={styles.RadioContainer} key={student.id}>
            <Text style={styles.studenttitle}>
              {student.firstName} {student.lastName}
            </Text>
            <View style={styles.RadioDiv}>
              <RadioButton
                value="first"
                status={checked === "first" ? "checked" : "unchecked"}
                onPress={() => {
                  handleStatus(student.id, "present"), setChecked("first");
                }}
              />
              <Text style={styles.Radiotitle}>Present</Text>
            </View>
            <View style={styles.RadioDiv}>
              <RadioButton
                value="second"
                status={checked === "second" ? "checked" : "unchecked"}
                onPress={() => {
                  handleStatus(student.id, "absent"), setChecked("second");
                }}
              />
              <Text style={styles.Radiotitle}>Absent</Text>
            </View>
            <View style={styles.RadioDiv}>
              <RadioButton
                value="thired"
                status={checked === "thired" ? "checked" : "unchecked"}
                onPress={() => {
                  handleStatus(student.id, "late"), setChecked("thired");
                }}
              />
              <Text style={styles.Radiotitle}>Late</Text>
            </View>
          </View>
        ) : null
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  RadioDiv: {
    flexDirection: "row",
    justifyContent: "center",
  },
  RadioContainer: {
    marginTop: 20,
    width: 300,
    backgroundColor: "white",
    padding: 16,
    marginLeft:10,
  },
  studenttitle: {
    color: "blue",
    fontSize: 24,
    fontWeight: "bold",
    textAlign: "center",
  },
  Radiotitle: {
    color: "black",
    fontSize: 20,
    fontWeight: "bold",
    textAlign: "center",
    marginTop: 5,
  },
  container: {
    flex: 1,
    paddingHorizontal: 20,
    paddingTop: 40,
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 20,
    marginLeft: 10,
    marginRight: 10,
  },
  title: {
    fontSize: 30,
    fontWeight: "bold",
    color: "blue",
  },
  logoutButton: {
    backgroundColor: "blue",
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderRadius: 4,
  },
  logoutButtonText: {
    color: "white",
    fontWeight: "bold",
  },
  Container: {
    backgroundColor: "white",
    padding: 16,
  },
  dropdown: {
    height: 50,
    borderColor: "gray",
    borderWidth: 0.5,
    borderRadius: 8,
    paddingHorizontal: 8,
  },
  icon: {
    marginRight: 5,
  },
  label: {
    position: "absolute",
    backgroundColor: "white",
    left: 22,
    top: 8,
    zIndex: 999,
    paddingHorizontal: 8,
    fontSize: 14,
  },
  placeholderStyle: {
    fontSize: 16,
  },
  selectedTextStyle: {
    fontSize: 16,
  },
  iconStyle: {
    width: 20,
    height: 20,
  },
  inputSearchStyle: {
    height: 40,
    fontSize: 16,
  },
});

export default Attendance;
